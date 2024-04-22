const baseURL = process.env.REACT_APP_API_URL;
const token = localStorage.getItem('app_token');

export function axiosApiInstance({ path, method, params, headers = {}, data }) {
    const apiHeaders = {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        Authorization: `Bearer ${token}`,
        ...headers
    };

    const requestInstance = {
        method,
        params,
        url: path,
        baseURL,
        headers: apiHeaders
    };

    if ((!token && !path) || !method) {
        return {};
    }

    if (data) {
        return {
            ...requestInstance,
            data
        };
    }

    return requestInstance;
}