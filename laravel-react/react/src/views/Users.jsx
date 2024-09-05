import { useEffect, useState } from "react"
import axiosClient from "../axios-client"
import { Link, useLocation } from "react-router-dom"
import { useStateContext } from "../contexts/ContextProvider"
import Pagination from "../components/Pagination"

export default function Users() {
  const [users, setUsers] = useState([])
  const [loading, setLoading] = useState(false)
  const [currentPage, setCurrentPage] = useState(new URLSearchParams(useLocation().search).get('page') || 1);
  const [totalPages, setTotalPages] = useState(1);
  const { setErrorNotification, setNotification, user } = useStateContext()

  useEffect(() => {
    getUsers(currentPage)
  }, [currentPage])

  const onDelete = (u) => {
    if (u.id == user.id) {
      setErrorNotification("You are already logged in with user " + u.name)
    } else if (!window.confirm("Are you sure you want to delete user " + u.name + "?")) {
      return
    } else {
      axiosClient.delete('/users/' + u.id).then(() => {
        setNotification("User " + u.name + " was succesfully deleted")
        getUsers(currentPage)
      })
    }
  }

  const getUsers = (page = 1) => {
    setLoading(true)
    axiosClient.get('/users?page=' + page).then(({ data }) => {
      setUsers(data.data)
      setTotalPages(data.meta.last_page)
      setCurrentPage(page > data.meta.last_page ? data.meta.last_page : data.meta.current_page)
      setLoading(false)
    }).catch(() => {
      setLoading(false)
    })
  }

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
        <h1>Users</h1>
        <Link to="/users/new" className="btn-add">Add new</Link>
      </div>
      <div className="card animated fadeInDown">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Create Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          {loading && <tbody>
            <tr>
              <td colSpan="5" className="text-center">
                Loading...
              </td>
            </tr>
          </tbody>}
          {!loading && <tbody>
            {users.map(u => (
              <tr key={u.id}>
                <td>{u.id}</td>
                <td>{u.name}</td>
                <td>{u.email}</td>
                <td>{u.created_at}</td>
                <td>
                  <Link className="btn-edit" to={'/users/' + u.id + (currentPage > 1 ? '?page=' + currentPage : '')}>Edit</Link>
                  &nbsp;
                  <button onClick={ev => onDelete(u)} className="btn-delete">Delete</button>
                </td>
              </tr>
            ))}
          </tbody>}
        </table>
      </div>
      {!loading && <Pagination
        currentPage={currentPage}
        totalPages={totalPages}
        onPageChange={setCurrentPage}
      />}
    </div>
  )
}
