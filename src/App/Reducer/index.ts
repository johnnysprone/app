import { combineReducers } from 'redux';

import api from 'Api';
import Authentication from 'Reducer/Authentication';

const rootReducer = combineReducers({
  Authentication,
  [api.reducerPath]: api.reducer,
});

export default rootReducer;
