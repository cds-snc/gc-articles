const CDS_VARS = window.CDS_VARS || {};
const requestHeaders = new Headers();
requestHeaders.append('X-WP-Nonce', CDS_VARS.rest_nonce);

interface ErrorWithStatus extends Error {
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
