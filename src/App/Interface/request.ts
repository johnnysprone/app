export interface Error {
  data: {
    message: string;
    errors: {
      [field: string]: {
        [error: string]: string;
      };
    };
  };
}

export interface Params extends Object {
  id?: number | string;
  finder?: string;
  page?: number | string;
  limit?: number | string;
  sort?: string;
  direction?: 'asc' | 'desc';
  contain?: string[];
  options?: {
    [key: string]: any;
  };
}
