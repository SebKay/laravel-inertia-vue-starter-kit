import type { PageProps, UserDocument } from "@js/types/inertia";

export function userCan(props: PageProps, permission: string): boolean {
    const user = props?.auth?.user;

    if (!user) {
        return false;
    }

    const can = (user as UserDocument).data.attributes.can;

    return can.includes(permission);
}
