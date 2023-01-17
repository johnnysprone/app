import { fetchBaseQuery, createApi } from '@reduxjs/toolkit/query/react';
import { Mutex } from 'async-mutex';
import { REHYDRATE } from 'redux-persist';

import { path } from 'Config';
import { Login, Logout } from 'Interface/sessao';
import { Usuario } from 'Interface/usuario';
import { token, logout } from 'Reducer/Authentication';
import { Selector } from 'Store/types';

const mutex = new Mutex();
const baseUrl = process.env.NODE_ENV === 'development' ? 'http://localhost:8765/api/v1' : '';

const baseQuery = fetchBaseQuery({
  baseUrl,
  prepareHeaders(headers, { getState }) {
    const { accessToken } = (getState() as Selector).Authentication;
    if (accessToken) {
      headers.set('authorization', `Bearer ${accessToken}`);
    }

    return headers;
  },
});

const baseQueryWithRefreshToken = async (
  args: any,
  api: any,
  extraOptions: any,
) => {
  let request = await baseQuery(args, api, extraOptions);

  await mutex.waitForUnlock();

  if (
    request.error
    && request.error.status === 401
    && args.url !== 'sessoes/login'
  ) {
    if (!mutex.isLocked()) {
      const { accessToken, refreshToken } = api.getState().authentication;
      const release = await mutex.acquire();
      try {
        const refresh: any = await baseQuery(
          {
            credentials: 'include',
            url: 'sessoes/token',
            method: 'POST',
            body: { accessToken, refreshToken },
          },
          api,
          extraOptions,
        );
        if (refresh.meta.response.status === 200) {
          api.dispatch(token(refresh.data));
          request = await baseQuery(args, api, extraOptions);
        } else {
          api.dispatch(logout());
          window.location.href = path.baseUrl;
        }
      } finally {
        release();
      }
    }
  } else {
    await mutex.waitForUnlock();
  }

  return request;
};

const api = createApi({
  reducerPath: 'api',
  baseQuery: baseQueryWithRefreshToken,
  refetchOnMountOrArgChange: 60,
  extractRehydrationInfo(action, { reducerPath }) {
    if (action.payload && action.type === REHYDRATE) {
      return action.payload[reducerPath];
    }

    return undefined;
  },
  tagTypes: ['Sessoes', 'Usuarios'],
  endpoints: (builder) => ({
    loginSessao: builder.mutation<Login, Partial<Usuario>>({
      query: (data) => ({ url: 'sessoes/login', method: 'POST', body: data }),
    }),
    tokenSessao: builder.mutation<Login, Partial<Login>>({
      query: (data) => ({ url: 'sessoes/token', method: 'POST', body: data }),
    }),
    logoutSessao: builder.mutation<Logout, string>({
      query: (data) => ({
        url: 'sessoes/logout',
        method: 'POST',
        body: { refreshToken: data },
      }),
    }),
  }),
});

export const {
  useLoginSessaoMutation,
  useTokenSessaoMutation,
  useLogoutSessaoMutation,
} = api;

export default api;
