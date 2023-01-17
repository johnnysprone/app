import React from 'react';
import ReactDOM from 'react-dom';

if (process.env.NODE_ENV === 'development') {
  // eslint-disable-next-line global-require
  const axe = require('@axe-core/react');
  axe(React, ReactDOM, 1000);

  // eslint-disable-next-line global-require
  const whyDidYouRender = require('@welldone-software/why-did-you-render');
  whyDidYouRender(React, {
    trackAllPureComponents: true,
  });
}
