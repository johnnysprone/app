import { store } from 'Store';

export type Dispatch = typeof store.dispatch;
export type Selector = ReturnType<typeof store.getState>;
