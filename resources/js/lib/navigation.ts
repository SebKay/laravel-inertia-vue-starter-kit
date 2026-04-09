export function hasActiveComponent(
    currentComponent: string,
    components?: string[],
): boolean {
    return (components ?? []).includes(currentComponent);
}
