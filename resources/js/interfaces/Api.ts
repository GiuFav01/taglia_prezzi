export default interface Api {
    id: string;
    url: string;
    description: string;
    tags: Tag[];
    lastExecution: string;
}
