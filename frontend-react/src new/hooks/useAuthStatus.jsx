// hooks/useAuthStatus.jsx
import { useEffect, useState } from "react";
import axios from "axios";

export function useAuthStatus() {
  const [loggedIn, setLoggedIn] = useState(false);
  const [checkingStatus, setCheckingStatus] = useState(true);

  useEffect(() => {
    async function checkAuthStatus() {
      try {
        const response = await axios.get('localhost:8000/api/user', {
          withCredentials: true
        });
        if (response.data) {
          setLoggedIn(true);
        }
      } catch (error) {
        setLoggedIn(false);
      } finally {
        setCheckingStatus(false);
      }
    }
    
    checkAuthStatus();
  }, []);

  return { loggedIn, checkingStatus };
}