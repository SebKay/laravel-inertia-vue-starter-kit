import "@inertiajs/core";

export interface UserAttributes {
    email?: string;
    name?: string;
    emailVerified: boolean;
    can: string[];
}

export type JsonApiResourceObject<
    TAttributes extends Record<string, unknown>,
    TType extends string,
> = {
    id: string;
    type: TType;
    attributes: TAttributes;
};

export type JsonApiDocument<
    TAttributes extends Record<string, unknown>,
    TType extends string,
> = {
    data: JsonApiResourceObject<TAttributes, TType>;
};

export type UserDocument = JsonApiDocument<UserAttributes, "users">;

export interface FlashData {
    success?: string;
    error?: string;
    warning?: string;
}

export interface LayoutProps {
    heading?: string;
}

export interface SharedPageProps {
    auth: {
        user: UserDocument | null;
    };
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, never>,
> = T & SharedPageProps;

declare module "@inertiajs/core" {
    interface InertiaConfig {
        flashDataType: FlashData;
        sharedPageProps: SharedPageProps;
        layoutProps: LayoutProps;
    }
}
