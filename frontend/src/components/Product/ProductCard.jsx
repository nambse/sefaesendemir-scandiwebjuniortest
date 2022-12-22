import { Component, createRef } from "react";

export default class ProductCard extends Component {
  checkboxRef = createRef();

  handleCardClick = (e) => {
    if (e.target.className === "product-card") {
      this.checkboxRef.current.click();
    }
  };

  render() {
    const {
      id,
      sku,
      name,
      price,
      size,
      height,
      width,
      length,
      weight,
      type,
      checkProduct,
      unCheckProduct,
    } = this.props;

    const dimensions = `${height}x${width}x${length}`;
    return (
      <div className="product-card" onClick={(e) => this.handleCardClick(e)}>
        <input
          id={id}
          type="checkbox"
          className="delete-checkbox"
          value={id}
          ref={this.checkboxRef}
          onChange={(e) =>
            e.target.checked ? checkProduct(id) : unCheckProduct(id)
          }
        />
        <div className="product-card__sku">{sku}</div>
        <div className="product-card__name">{name}</div>
        <div className="product-card__price">{price ? `${price}$` : null}</div>
        <div className="product-card__size">
          {type === "1" ? `Size: ${size}MB` : null}
        </div>
        <div className="product-card__weight">
          {type === "2" ? `Weight: ${weight}KG` : null}
        </div>
        <div className="product-card__dimensions">
          {type === "3" ? `Dimension: ${dimensions}` : null}
        </div>
      </div>
    );
  }
}
