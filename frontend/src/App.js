import { Component } from "react";
import { BrowserRouter, Route, Routes } from "react-router-dom";
import ProductList from "./pages/ProductList";
import "./scss/style.scss";
import ProductAdd from "./pages/ProductAdd";

class App extends Component {
  render() {
    return (
      <BrowserRouter>
        <Routes>
          <Route index element={<ProductList />} />
          <Route path="/addproduct" element={<ProductAdd />} />
        </Routes>
      </BrowserRouter>
    );
  }
}

export default App;
