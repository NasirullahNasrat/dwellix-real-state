import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api', // Your Laravel base URL
  withCredentials: true,
});

export default api;