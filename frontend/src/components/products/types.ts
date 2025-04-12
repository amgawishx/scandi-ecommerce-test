export interface Price {
  id: number;
  amount: number;
  currencyLabel: string;
  currencySymbol: string;
}

export interface Gallery {
  id: number;
  imageUrl: string;
}

export interface Attribute {
  id: number;
  name: string;
  type: string;
}

export interface AttributeValue {
  id: number;
  value: string;
  displayValue: string;
  attribute: Attribute;
}

export interface ProductAttribute {
  attributeValue: AttributeValue;
}

export interface CartItem {
  id: string;
  name: string;
  price: number;
  quantity: number;
  image: string;
  selectedAttributes: {
    [key: string]: {
      id: number;
      value: string;
    };
  };
  availableAttributes?: {
    [key: string]: string[];
  };
}

export interface Product {
  id: string;
  name: string;
  description: string;
  category: string;
  brand: string | null;
  inStock: boolean;
  prices: Price[];
  galleries: Gallery[];
  attributes: ProductAttribute[];
} 