import { useEffect, useState } from "react"
import { useNavigate, useParams, useLocation } from "react-router-dom"
import axiosClient from "../axios-client"
import { useStateContext } from "../contexts/ContextProvider"

function UserForm() {
  const { id } = useParams()
  const navigate = useNavigate()
  const page = new URLSearchParams(useLocation().search).get('page');
  const [loading, setLoading] = useState(false)
  const [errors, setErrors] = useState(null)
  const { setNotification, setUser, user } = useStateContext()
  const [_user, _setUser] = useState({
    id: null,
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
  })

  if (id) {
    useEffect(() => {
      setLoading(true)
      axiosClient.get('/users/' + id).then(({ data }) => {
        setLoading(false)
        _setUser(data)
      }).catch(() => {
        setLoading(false)
      })
    }, [])
  }

  const onSubmit = (ev) => {
    ev.preventDefault()
    setErrors(null)
    if (_user.id) {
      axiosClient.put('/users/' + _user.id, _user).then(() => {
        if (user.id == _user.id) {
          setUser(_user)
        }
        setNotification("User " + _user.name + " was succesfully updated")
        navigate(`/users${page && page > 1 ? `?page=${page}` : ''}`);
      }).catch(err => {
        const response = err.response;
        if (response && response.status == 422) {
          setErrors(response.data.errors)
        }
      })
    } else {
      axiosClient.post('/users', _user).then(() => {
        setNotification("User " + _user.name + " was succesfully created")
        navigate('/users')
      }).catch(err => {
        const response = err.response;
        if (response && response.status == 422) {
          setErrors(response.data.errors)
        }
      })
    }
  }

  return (
    <>
      {_user.id && <h1>Update User: {_user.name}</h1>}
      {!_user.id && <h1>New User</h1>}
      <div className="card animated fadeInDown">
        {loading && (
          <div className="text-center">Loading...</div>
        )}
        {errors && <div className="alert">
          {Object.keys(errors).map(key => (
            <p key={key}>{errors[key][0]}</p>
          ))}
        </div>}
        {!loading && (
          <form onSubmit={onSubmit}>
            <input value={_user.name} onChange={ev => _setUser({ ..._user, name: ev.target.value })} placeholder="nameName" />
            <input type="email" value={_user.email} onChange={ev => _setUser({ ..._user, email: ev.target.value })} placeholder="Email" />
            <input type="password" onChange={ev => {
              const { value } = ev.target
              _setUser(_user => {
                const updatedUser = { ..._user }
                if (value) {
                  updatedUser.password = value
                } else {
                  delete updatedUser.password
                }
                return updatedUser
              })
            }} placeholder="Password" />
            <input type="password" onChange={ev => _setUser({ ..._user, password_confirmation: ev.target.value })} placeholder="Password Confirmation" />
            <button className="btn">Save</button>
          </form>
        )}
      </div>
    </>
  )
}

export default UserForm
