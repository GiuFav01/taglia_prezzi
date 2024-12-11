import Api  from './Api';

export interface Asin {
    id: string;
    asin: string;
    id_api: string;
    api?: Api;
    created_at?: string;
    updated_at?: string;
}
