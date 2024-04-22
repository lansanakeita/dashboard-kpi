export function createApiRequestInstance({
  path,
  method,
  params,
  headers = {},
  data,
  signal,
}) {
  const token = localStorage.getItem("app_token") || "";

  const apiHeaders = {
    "Content-Type": "application/json",
    Accept: "application/json",
    Authorization: `Bearer ${token}`,
    ...headers,
  };

  const requestInstance = {
    method,
    params,
    url: path,
    baseURL: process.env.NEXT_PUBLIC_API_URL,
    headers: apiHeaders,
    signal,
  };

  if ((!token && !path) || !method) {
    return;
  }

  if (data) {
    return {
      ...requestInstance,
      data,
    };
  }

  return requestInstance;
}
