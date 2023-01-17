import React from 'react';
import { Navigate } from 'react-router-dom';

import { path } from 'Config';
import { Provider } from 'Interface/provider';
import { useAppSelector } from 'Store/hooks';

function Authorization({ children }: Provider): JSX.Element {
  const { usuario } = useAppSelector((state) => state.Authentication);

  if (!usuario) {
    return <Navigate to={path.baseUrl} />;
  }

  return <div id="screen">{children}</div>;
}

export default Authorization;
