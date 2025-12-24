import type { PageProps } from "@js/types/inertia";

export function userCan(props: PageProps, permission: string): boolean {
    const user = props?.auth?.user;

    if (!user || Array.isArray(user)) {
        return false;
    }

    return user.can.includes(permission);
}
