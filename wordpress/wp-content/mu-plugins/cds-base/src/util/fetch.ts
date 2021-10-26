const CDS_VARS = window.CDS_VARS || {};
const headers = new Headers();
headers.append('X-WP-Nonce', CDS_VARS.rest_nonce);

export const requestHeaders = headers;
export interface ErrorWithStatus extends Error {
  status: number;
}

export const getData = async (endpoint: string) => {
  const response = await fetch(`${CDS_VARS.rest_url}${endpoint}`, {
    method: 'GET',
    headers: requestHeaders,
    mode: 'cors',
    cache: 'default',
  });

  if (!response.ok) {
    console.log(response.body);
    const err = new Error(`HTTP error`) as ErrorWithStatus;
    err.status = response.status;
    throw err;
  }

  return await response.json();
};

export const sendData = async (endpoint: string, data) => {
  const response = await fetch(`${CDS_VARS.rest_url}${endpoint}`, {
      method: 'POST',
      headers: requestHeaders,
      mode: 'cors',
      cache: 'default',
      body: JSON.stringify(data)
  });

  if (!response.ok) {
    console.log(response.body);
    const err = new Error(`HTTP error`) as ErrorWithStatus;
    err.status = response.status;
    throw err;
  }

  return await response.json();
};
