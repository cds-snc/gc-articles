export const sendListData = async (
  endpoint: string | undefined,
  rest_nonce: string | undefined,
  data: {}
) => {
  if (!endpoint || !rest_nonce) return false;

  const headers = new Headers({
    'Content-Type': 'application/json;charset=UTF-8',
  });

  headers.append('X-WP-Nonce', rest_nonce);

  const response = await fetch(endpoint, {
    method: 'POST',
    headers: headers,
    mode: 'cors',
    cache: 'default',
    body: JSON.stringify(data),
  });

  if (!response.ok) {
    console.log(response.body);
    console.log(response);
  }
};
