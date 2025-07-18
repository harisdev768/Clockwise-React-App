// apiClient.ts
import axios from "axios";

let accessToken: string | null = null;

export const setAccessToken = (token: string) => {
  accessToken = token;

  const expiresIn = 60 * 60 * 1000; // 1 hour in milliseconds
  const expiryTime = Date.now() + expiresIn;

  localStorage.setItem("accessTokenExpiry", expiryTime.toString());
  
};

const apiClient = axios.create({
  baseURL: "/api",
  withCredentials: true,
  headers: {
    "Content-Type": "application/json",
  },
});
// Request interceptor
apiClient.interceptors.request.use(
  function (config) {
    if (accessToken) {
      config.headers.Authorization = accessToken;
    }
    return config;
  },
  function (error) {
    return Promise.reject(error);
  }
);

// Response interceptor
apiClient.interceptors.response.use(
  function (response) {
    return response;
  },
  function (error) {
    return Promise.reject(error);
  }
);

export default apiClient;
