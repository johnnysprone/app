import React from 'react';
import { createRoot } from 'react-dom/client';
import { Provider } from 'react-redux';
import { BrowserRouter } from 'react-router-dom';
import { PersistGate } from 'redux-persist/integration/react';

import Router from 'Config/Router';
import { persistor, store } from 'Store';

import './index.scss';
import 'bootstrap';

require('Config/bootstrap');

const app = createRoot(document.getElementById('app')!);

app.render(
  <React.StrictMode>
    <Provider store={store}>
      <PersistGate
        loading={null}
        persistor={persistor}
      >
        <BrowserRouter>
          <Router />
        </BrowserRouter>
      </PersistGate>
    </Provider>
  </React.StrictMode>,
);
