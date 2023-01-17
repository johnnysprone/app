import { TypedUseSelectorHook, useDispatch, useSelector } from 'react-redux';

import { Dispatch, Selector } from 'Store/types';

export const useAppDispatch = () => useDispatch<Dispatch>();
export const useAppSelector: TypedUseSelectorHook<Selector> = useSelector;
