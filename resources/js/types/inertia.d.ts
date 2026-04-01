import '@inertiajs/core';

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

export interface LayoutProps {
    heading?: string;
    subheading?: string;
    contentClass?: string;
}

export interface SharedPageProps {
    auth: {
        user: User | [];
    };
}

export type PageProps<T extends Record<string, unknown> = Record<string, never>> = T & SharedPageProps;

declare module '@inertiajs/core' {
    interface InertiaConfig {
        flashDataType: FlashData;
        sharedPageProps: SharedPageProps;
        layoutProps: LayoutProps;
    }
}
