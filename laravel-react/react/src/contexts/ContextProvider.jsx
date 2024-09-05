
// with respect to PHP concepts: importing the usage of classes (use)
import { createContext, useContext, useState } from "react";

// with respect to PHP concepts: interface
const StatContext = createContext({
  user: null,
  token: null,
  notification: null,
  errorNotification: null,
  setUser: () => { },
  setToken: () => { },
  setNotification: () => { },
  setErrorNotification: () => { }
})

// with respect to PHP concepts: the class that implments the interface
export const ContextProvider = ({ children }) => {
  const [user, setUser] = useState({});
  const [notification, _setNotification] = useState('');
  const [errorNotification, _setErrorNotification] = useState('');
  const [token, _setToken] = useState(localStorage.getItem('ACCESS_TOKEN'));
  const setToken = (token) => {
    _setToken(token)
    if (token) {
      localStorage.setItem('ACCESS_TOKEN', token);
    } else {
      localStorage.removeItem('ACCESS_TOKEN');
    }
  }

  const setNotification = (message) => {
    _setNotification(message);
    setTimeout( () => {
      _setNotification('')
    }, 5000)
  }

  const setErrorNotification = (message) => {
    _setErrorNotification(message);
    setTimeout( () => {
      _setErrorNotification('')
    }, 5000)
  }

  return (
    <StatContext.Provider value={{
      user,
      token,
      setUser,
      setToken,
      notification,
      errorNotification,
      setNotification,
      setErrorNotification
    }}>
      {children}
    </StatContext.Provider>
  )
}

// with respect to PHP concepts: dependancy injection with instantiated object
export const useStateContext = () => useContext(StatContext)
