import { createSlice, PayloadAction } from '@reduxjs/toolkit';

import { Login } from 'Interface/sessao';

const initialState: Partial<Login> = {};

const Authentication = createSlice({
  initialState,
  name: 'authentication',
  reducers: {
    login: (_state, action: PayloadAction<Login>) => action.payload,
    token: (state, action: PayloadAction<Login>) => ({
      ...state,
      accessToken: action.payload.accessToken,
      refreshToken: action.payload.refreshToken,
      expires: action.payload.expires,
    }),
    logout: () => initialState,
  },
});

export const { login, logout, token } = Authentication.actions;

export default Authentication.reducer;
