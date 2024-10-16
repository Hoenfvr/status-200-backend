import { BrowserRouter, Routes, Route } from "react-router-dom";
import "./App.css";
import ListRoom from "./componants/listroom";
import CreateRoom from "./componants/CreateRoom";

function App() {
  return (
    <>
      <div className="App">
        <h2>Vite+React MySQL</h2>
        <BrowserRouter>
          <Routes>
            <Route path="/" element={<ListRoom />} />
            <Route path="listroom/create" element={<CreateRoom />} />
            <Route></Route>
          </Routes>
        </BrowserRouter>
      </div>
    </>
  );
}

export default App;
