export interface User {
    id: number;
    name: string;
    username: string;
    isAdmin: boolean;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
};
