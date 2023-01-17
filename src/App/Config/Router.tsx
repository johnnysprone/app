import React from 'react';
import { Routes, Route } from 'react-router-dom';

import { path } from 'Config';

function Router(): JSX.Element {
  return (
    <Routes>
      <Route
        element=""
        path={path.baseUrl}
      />
      <Route
        element=""
        path={path.redirectLogin}
      />
    </Routes>
  );
}

export default Router;
