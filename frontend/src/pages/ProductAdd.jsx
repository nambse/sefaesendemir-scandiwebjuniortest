import React, { Component } from "react";
import withRouter from "../components/HOC/withRouter";
import BASE_URL from "../api";

class ProductAdd extends Component {
  state = {
    name: "",
    sku: "",
    price: "",
    size: "",
    height: "",
    width: "",
    length: "",
    weight: "",
    type: "",
  };
  submitInputRef = React.createRef();

  handleChange = (e) => {
    this.setState({
      [e.target.name]: e.target.value,
    });
  };

  handleSubmit = (event) => {
    event.preventDefault();
    // Click submit the form input
    this.submitInputRef.current.click();
  };

  handleAdd = async (e) => {
    //If form can be submitted add the product
    e.preventDefault();

    const { name, sku, price, size, height, width, length, weight, type } =
      this.state;

    const typeId = type === "DVD" ? 1 : type === "Book" ? 2 : 3;

    const productToSend = {
      name: name,
      sku: sku,
      price: price,
      size: size,
      height: height,
      width: width,
      length: length,
      weight: weight,
      type_id: typeId,
    };
    const response = await fetch(`${BASE_URL}/create`, {
      method: "POST",
      body: JSON.stringify(productToSend),
    });
    const data = await response.json();
    //Check if the product is added successfully or the sku is already in use
    if (
      data.message ===
      `SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '${sku}' for key 'sku'`
    ) {
      document.getElementById("sku").style.border = "2px solid red";
      document.getElementById("sku").value = "";
      document.getElementById("sku").placeholder = "SKU is already in use.";
      return;
    }
    //If the product is added successfully, redirect to the product list page
    this.props.router.navigate("/");
  };

  handleTypeChange = (event) => {
    this.setState({ type: event.target.value });
  };

  render() {
    return (
      <>
        <div className="container">
          <div className="product-list__header">
            <div className="product-list__title">Product Add</div>
            <div className="product-list__buttons">
              <button
                className="product-list__button product-list__button--add"
                onClick={this.handleSubmit}
              >
                Save
              </button>
              <button
                type="submit"
                className="product-list__button product-list__button--delete"
                onClick={() => this.props.router.navigate("/")}
              >
                Cancel
              </button>
            </div>
          </div>
        </div>
        <form
          className="product-form"
          id="product_form"
          onSubmit={this.handleAdd}
        >
          <div className="product-form__input-group">
            <label className="product-form__label">
              SKU
              <input
                id="sku"
                className="product-form__input"
                type="text"
                name="sku"
                required
                value={this.state.sku}
                onChange={this.handleChange}
                onClick={() => {
                  document.getElementById("sku").style.border =
                    "1px solid #ddd";
                  document.getElementById("sku").placeholder = "";
                }}
              />
            </label>
            <br />
            <label className="product-form__label">
              Name
              <input
                id="name"
                className="product-form__input"
                type="text"
                name="name"
                required
                value={this.state.name}
                onChange={this.handleChange}
              />
            </label>
            <br />
            <label className="product-form__label">
              Price ($)
              <input
                id="price"
                className="product-form__input"
                type="number"
                style={{ appearance: "textfield" }}
                name="price"
                required
                value={this.state.price}
                onChange={this.handleChange}
              />
            </label>
            <br />
            <label className="product-form__label">
              Type Switcher
              <select
                id="productType"
                className="product-form__select"
                value={this.state.type}
                required={this.state.type === "DVD" || "Furniture" || "Book"}
                onChange={this.handleTypeChange}
              >
                <option value="">Select a type</option>
                <option value="DVD" id="DVD">
                  DVD
                </option>
                <option value="Book" id="Book">
                  Book
                </option>
                <option value="Furniture" id="Furniture">
                  Furniture
                </option>
              </select>
            </label>
            <br />
            {this.state.type === "DVD" && (
              <>
                <div className="product-form__special-description">
                  Please provide size in MB.
                </div>
                <label className="product-form__label">
                  Size (MB)
                  <input
                    id="size"
                    className="product-form__input"
                    type="number"
                    style={{ appearance: "textfield" }}
                    name="size"
                    required={this.state.type === "DVD"}
                    value={this.state.size}
                    onChange={this.handleChange}
                  />
                </label>
              </>
            )}
            {this.state.type === "Furniture" && (
              <div>
                <div className="product-form__special-description">
                  Please provide dimensions in HxWxL format.
                </div>
                <label className="product-form__label">
                  Height (CM)
                  <input
                    id="height"
                    className="product-form__input"
                    type="number"
                    style={{ appearance: "textfield" }}
                    name="height"
                    required={this.state.type === "Furniture"}
                    value={this.state.height}
                    onChange={this.handleChange}
                  />
                </label>
                <br />
                <label className="product-form__label">
                  Width (CM)
                  <input
                    id="width"
                    className="product-form__input"
                    type="number"
                    style={{ appearance: "textfield" }}
                    name="width"
                    required={this.state.type === "Furniture"}
                    value={this.state.width}
                    onChange={this.handleChange}
                  />
                  <br />
                </label>
                <label className="product-form__label">
                  Length (CM)
                  <input
                    id="length"
                    className="product-form__input"
                    type="number"
                    style={{ appearance: "textfield" }}
                    name="length"
                    required={this.state.type === "Furniture"}
                    value={this.state.length}
                    onChange={this.handleChange}
                  />
                  <br />
                </label>
              </div>
            )}
            {this.state.type === "Book" && (
              <div>
                <div className="product-form__special-description">
                  Please provide weight in KG.
                </div>
                <label className="product-form__label">
                  Weight (KG)
                  <input
                    id="weight"
                    className="product-form__input"
                    type="number"
                    style={{ appearance: "textfield" }}
                    name="weight"
                    required={this.state.type === "Book"}
                    value={this.state.weight}
                    onChange={this.handleChange}
                  />
                  <br />
                </label>
              </div>
            )}
          </div>
          <input type={"submit"} ref={this.submitInputRef} hidden />
        </form>
      </>
    );
  }
}

export default withRouter(ProductAdd);
