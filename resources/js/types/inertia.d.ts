export interface User {
    id: number;
    email?: string;
    first_name?: string;
    last_name?: string;
    can: string[];
}

export interface SharedPageProps {
    auth: {
        loggedIn: boolean;
        user: User | [];
    };
    success?: string;
    error?: string;
    warning?: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, never>> = T & SharedPageProps;

