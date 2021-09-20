export const delay = (ms = 10000) => {
    return new Promise(resolve => setTimeout(resolve, ms));
}