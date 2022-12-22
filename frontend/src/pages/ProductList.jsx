import { Component } from "react";
import ProductCard from "../components/Product/ProductCard";
import withRouter from "../components/HOC/withRouter";

class ProductList extends Component {
  state = {
    products: [],
    checkedProducts: [],
  };

  componentDidMount() {
    this.fetchProducts();
  }

  // https://scandiwebjuniortest-sefaesendemir.000webhostapp.com/api/product/read.php
  fetchProducts = async () => {
    try {
      const response = await fetch(
        "https://scandiwebjuniortest-sefaesendemir.000webhostapp.com/api/product/read.php"
      );
      const products = await response.json();
      this.setState({ products });
    } catch (error) {
      console.log(error);
    }
  };

  deleteCheckedProducts = async () => {
    await this.findCheckedProducts();
    try {
      if (this.state.checkedProducts.length > 0) {
        const productsToDelete = {
          id: this.state.checkedProducts,
        };
        await fetch(
          "https://scandiwebjuniortest-sefaesendemir.000webhostapp.com/api/product/delete.php",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(productsToDelete),
          }
        );
        this.setState((prevState) => {
          const filteredProducts = prevState.products.filter(
            (product) => !prevState.checkedProducts.includes(product.id)
          );
          return { products: filteredProducts, checkedProducts: [] };
        });
      }
    } catch (error) {
      console.log(error);
    }
  };

  //Extra function to be sure about getting all the checked products.
  findCheckedProducts = () => {
    const checkboxes = document.getElementsByClassName("delete-checkbox");
    for (let i = 0; i < checkboxes.length; i++) {
      if (!this.state.checkedProducts.includes(checkboxes[i].id)) {
        if (checkboxes[i].checked === true) {
          this.state.checkedProducts.push(checkboxes[i].id);
        }
      }
    }
  };

  checkProduct = (id) => {
    this.setState((prevState) => {
      if (!prevState.checkedProducts.includes(id)) {
        const checkedProducts = [...prevState.checkedProducts, id];
        return { checkedProducts };
      }
    });
  };

  unCheckProduct = (id) => {
    this.setState((prevState) => {
      const checkedProducts = prevState.checkedProducts.filter(
        (item) => item !== id
      );
      return { checkedProducts: checkedProducts };
    });
  };

  render() {
    const { products } = this.state;
    return (
      <div className="container">
        <div className="product-list__header">
          <div className="product-list__title">Product List</div>
          <div className="product-list__buttons">
            <button
              className="product-list__button product-list__button--add"
              onClick={() => this.props.router.navigate("/addproduct")}
            >
              ADD
            </button>
            <button
              className="product-list__button product-list__button--delete"
              id="delete-product-btn"
              onClick={this.deleteCheckedProducts}
            >
              MASS DELETE
            </button>
          </div>
        </div>
        <div className="product-list">
          {products.length ? (
            products.map(
              ({
                id,
                sku,
                name,
                price,
                size,
                height,
                width,
                length,
                weight,
                type_id,
              }) => (
                <ProductCard
                  key={id}
                  id={id}
                  sku={sku}
                  name={name}
                  price={parseFloat(price).toFixed(2)}
                  size={size}
                  height={height}
                  width={width}
                  length={length}
                  weight={parseFloat(weight).toFixed(2)}
                  type={type_id}
                  checkProduct={this.checkProduct}
                  unCheckProduct={this.unCheckProduct}
                />
              )
            )
          ) : (
            <div className="no-product">No products found..</div>
          )}
        </div>
      </div>
    );
  }
}

export default withRouter(ProductList);
