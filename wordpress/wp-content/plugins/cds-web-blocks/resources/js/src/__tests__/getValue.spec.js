import { getValue } from "../utils/util";

test('parses value from saved', () => {
    const value = getValue("cds_product", 'saved value');
    expect(value).toBe("saved value");
});

test('parses value from saved when empty', () => {
    const value = getValue("cds_product", '');
    expect(value).toBe("");
});

test('parses sub value from saved', () => {
    const value = getValue("cds_product:name", '{\"name\":\"saved value\"}');
    expect(value).toBe("saved value");
});

test('parses missing sub value as empty', () => {
    const value = getValue("cds_product:name", '{\"description\":\"saved value\"}');
    expect(value).toBe("");
});

test('parses sub values from saved with additonal properties', () => {
    const name = getValue("cds_product:name", '{\"name\":\"saved value!\",\"description\":\"new value!\"}');
    expect(name).toBe("saved value!");

    const description = getValue("cds_product:description", '{\"name\":\"saved value!\",\"description\":\"saved value!!\"}');
    expect(description).toBe("saved value!!");
});