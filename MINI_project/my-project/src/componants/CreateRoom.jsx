import { useState, useEffect } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";

function CreateRoom() {
  const [inputs, setInputs] = useState({
    room_name: "",
    room_type: "",
    room_size: "",
    floor: "",
    building_id: "",
    status_active: "",
    create_by: "",
    create_date: new Date().toISOString().split("T")[0], // Default to today's date
  });

  const [buildings, setBuildings] = useState([]);
  const [loading, setLoading] = useState(false); // Add loading state
  const navigate = useNavigate();

  useEffect(() => {
    const fetchBuildings = async () => {
      setLoading(true);
      try {
        const response = await axios.get(
          "http://localhost/MINI_PROJECT/buildings.php"
        );
        setBuildings(response.data);
        console.log("Buildings fetched:", response.data);
      } catch (error) {
        console.error("There was an error fetching the buildings!", error);
      } finally {
        setLoading(false);
      }
    };

    fetchBuildings();
  }, []);

  const handleChange = (event) => {
    const name = event.target.name;
    const value = event.target.value;

    console.log(`Input changed - Field: ${name}, Value: ${value}`);
    setInputs((prev) => ({ ...prev, [name]: value }));
  };

  const handleAddData = async (event) => {
    event.preventDefault();
    console.log("Submitting data:", inputs);

    for (const key in inputs) {
      if (!inputs[key]) {
        alert(`Please fill out the ${key} field.`);
        return;
      }
    }

    if (inputs.room_size <= 0 || inputs.floor <= 0) {
      alert("Room size and floor must be positive numbers.");
      return;
    }

    setLoading(true); // Set loading state to true while submitting

    try {
      const response = await axios.post(
        "http://localhost/MINI_PROJECT/meeting_room.php",
        inputs
      );
      console.log("Response from API:", response.data);
      alert(response.data.message);
      if (response.data.status === 1) {
        navigate("/");
      }
    } catch (error) {
      console.error("There was an error submitting the form!", error);
      alert("There was an error submitting the form!");
    } finally {
      setLoading(false); // Reset loading state
    }
  };

  return (
    <div>
      <h2>Create Room</h2>
      <form onSubmit={handleAddData}>
        <div>
          <label>Room Name:</label>
          <input
            type="text"
            name="room_name"
            className="text-input"
            value={inputs.room_name}
            onChange={(e) => handleChange(e)}
            required
          />
        </div>
        <div>
          <label>Room Type:</label>
          <input
            type="text"
            name="room_type"
            className="text-input"
            value={inputs.room_type}
            onChange={(e) => handleChange(e)}
            required
          />
        </div>
        <div>
          <label>Room Size:</label>
          <input
            type="number"
            name="room_size"
            className="text-input"
            value={inputs.room_size}
            onChange={(e) => handleChange(e)}
            required
          />
        </div>
        <div>
          <label>Floor:</label>
          <input
            type="number"
            name="floor"
            className="text-input"
            value={inputs.floor}
            onChange={(e) => handleChange(e)}
            required
          />
        </div>
        <div>
          <label>Building:</label>
          <select
            name="building_id"
            value={inputs.building_id}
            onChange={(e) => handleChange(e)}
            required
          >
            <option value="">Select a building</option>
            {buildings.length > 0 ? (
              buildings.map((building) => (
                <option key={building.id} value={building.id}>
                  {building.id}
                </option>
              ))
            ) : (
              <option disabled>No buildings available</option>
            )}
          </select>
        </div>
        <div>
          <label>Status Active:</label>
          <select
            name="status_active"
            value={inputs.status_active}
            onChange={(e) => handleChange(e)}
            required
          >
            <option value="">Select status</option>
            <option value="0">active</option>
            <option value="1">inactive</option>
          </select>
        </div>
        <div>
          <label>Create By:</label>
          <input
            type="text"
            name="create_by"
            className="text-input"
            value={inputs.create_by}
            onChange={(e) => handleChange(e)}
            required
          />
        </div>
        <div>
          <label>Create Date:</label>
          <input
            type="date"
            name="create_date"
            className="text-input"
            value={inputs.create_date}
            onChange={(e) => handleChange(e)}
            required
          />
        </div>

        <button
          onClick={handleAddData}
          type="submit"
          className="btn btn-primary"
          disabled={loading}
        >
          {loading ? "Creating..." : "Create Room"}
        </button>
      </form>
    </div>
  );
}

export default CreateRoom;
