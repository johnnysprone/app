export interface Paginate {
  count: number;
  current: number;
  perPage: number;
  page: number;
  requestedPage: number;
  pageCount: number;
  start: number;
  end: number;
  prevPage: boolean;
  nextPage: boolean;
  sort: string;
  direction: string;
  sortDefault: boolean;
  directionDefault: boolean;
  completeSort: object;
  limit: number;
  scope: string;
  finder: string;
}
