import { parseMetaKey } from "../utils/util";

test('empty', () => {
    const { key, prop } = parseMetaKey("");
    expect(key).toBe("");
    expect(prop).toBe("");
});

test('parse meta key', () => {
    const { key, prop } = parseMetaKey("cds_product");
    expect(key).toBe("cds_product");
    expect(prop).toBe("");
});

test('parse sub meta key', () => {
    const { key, prop } = parseMetaKey("cds_product:name");
    expect(key).toBe("cds_product");
    expect(prop).toBe("name");
});