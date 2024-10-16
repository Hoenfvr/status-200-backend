import { useState, useEffect } from "react";
import axios from "axios";
import { Link } from "react-router-dom";

function ListRoom() {
  const [room, setRoom] = useState([]);
  const [loading, setLoading] = useState(true); // New loading state
  const [error, setError] = useState(null); // New error state

  // Fetch room data
  async function getRoom() {
    try {
      const response = await axios.get(
        "http://localhost/MINI_PROJECT/index.php/"
      );
      console.log(response.data);
      setRoom(response.data); // Set the room data
      setLoading(false); // Stop loading after data fetch
    } catch (error) {
      console.error("There was an error fetching the room data:", error);
      setError("Failed to fetch room data. Please try again later."); // Set error message
      setLoading(false); // Stop loading on error
    }
  }

  // Call getRoom when the component mounts
  useEffect(() => {
    getRoom();
  }, []);

  return (
    <div>
      <h2>List of Rooms</h2>
      <Link to="listroom/create">
        <button className="btn btn-info">Add Data</button>
      </Link>
      {loading ? ( // Show loading message
        <p>Loading rooms...</p>
      ) : error ? ( // Show error message if there's an error
        <p className="text-danger">{error}</p>
      ) : (
        <table className="table">
          <thead>
            <tr>
              <th>Room ID</th>
              <th>Name</th>
              <th>Type</th>
              <th>Size</th>
              <th>Floor</th>
              <th>Building ID</th>
              <th>Status</th>
              <th>Created By</th>
              <th>Created Date</th>
              <th>Updated By</th>
              <th>Updated Date</th>
            </tr>
          </thead>
          <tbody>
            {room.length > 0 ? (
              room.map((meeting_room) => (
                <tr key={meeting_room.id}>
                  <td>{meeting_room.id}</td>
                  <td>{meeting_room.room_name}</td>
                  <td>{meeting_room.room_type}</td>
                  <td>{meeting_room.room_size}</td>
                  <td>{meeting_room.floor}</td>
                  <td>{meeting_room.building_id}</td>
                  <td>{meeting_room.status_active}</td>
                  <td>{meeting_room.create_by}</td>
                  <td>{meeting_room.create_date}</td>
                  <td>{meeting_room.update_by}</td>
                  <td>{meeting_room.update_date}</td>
                </tr>
              ))
            ) : (
              <tr>
                <td colSpan="11">No data available</td>
              </tr>
            )}
          </tbody>
        </table>
      )}
    </div>
  );
}

export default ListRoom;
