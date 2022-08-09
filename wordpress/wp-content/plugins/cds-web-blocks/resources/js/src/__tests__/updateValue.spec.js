import { updateValue } from "../utils";

test('updates non sub field content', () => {
    const value = updateValue("cds_product", "new value", "old value");
    expect(value).toBe("new value");
});

test('updates from empty string', () => {
    const value = updateValue("cds_product:name", "new value", '');
    expect(value).toBe('{\"name\":\"new value\"}');
});

test('updates from empty object', () => {
    const value = updateValue("cds_product:description", "new value!", '{}');
    expect(value).toBe('{\"description\":\"new value!\"}');
});

test('updates sub field content', () => {
    const value = updateValue("cds_product:name", "new value", '{"name":"old value"}');
    expect(value).toBe('{\"name\":\"new value\"}');
});

test('retains existing object properties', () => {
    const value = updateValue("cds_product:description", "new value!", '{"name":"test"}');
    expect(value).toBe('{\"name\":\"test\",\"description\":\"new value!\"}');
});


test('handles slashes', () => {
    const data = JSON.stringify({name: "Mr. Jones's product"});
    const value = updateValue("cds_product:name", "Mr. Jones's product", '{"name":"old value"}');
    expect(value).toBe(data);
});