export interface User {
    id: number;
    email?: string;
    name?: string;
    can: string[];
}

export interface FlashData {
    success?: string;
    error?: string;
    warning?: string;
}

export interface SharedPageProps {
    auth: {
        loggedIn: boolean;
        user: User | [];
    };
    flash: FlashData;
}

export type PageProps<T extends Record<string, unknown> = Record<string, never>> = T & SharedPageProps;

