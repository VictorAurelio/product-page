async function fetchForm() {
    const response = await fetch('http://localhost/productpage/backend/public/add-product', {
      method: 'GET',
    });
  
    if (response.status === 200) {
      const result = await response.json();
      if (result.message === 'Form should be displayed on frontend') {
        // Exibir o formulário
        document.querySelector('#product_form').style.display = 'block';
      }
    } else {
      console.error(response.statusText);
    }
  }
  
  // Executar a função fetchForm ao carregar a página
  fetchForm();
/**
 * This part is responsible for manipulating the form and making it dynamic
 */

const productTypeInput = document.querySelector('#productType');
const productTypeSpecificDiv = document.querySelector('#productTypeSpecific');

productTypeInput.addEventListener('change', (event) => {
  const selectedType = event.target.value;
  if (selectedType === 'Dvd') {
    productTypeSpecificDiv.innerHTML = `
      <div>
        <label for="size">Size (MB):</label>
        <input type="number" id="size" name="size" step="any" required>
        <span>Please, provide size</span>
      </div>
    `;
  } else if (selectedType === 'Book') {
    productTypeSpecificDiv.innerHTML = `
      <div>
        <label for="weight">Weight (Kg):</label>
        <input type="number" id="weight" name="weight" step="any" required>
        <span>Please, provide weight</span>
      </div>
    `;
  } else if (selectedType === 'Furniture') {
    productTypeSpecificDiv.innerHTML = `
      <div>
        <label for="height">Height (cm):</label>
        <input type="number" id="height" name="height" step="any" required>
      </div>
      <div>
        <label for="width">Width (cm):</label>
        <input type="number" id="width" name="width" step="any" required>
      </div>
      <div>
        <label for="length">Length (cm):</label>
        <input type="number" id="length" name="length" step="any" required>
      </div>
      <span>Please, provide dimensions (HxWxL)</span>
    `;
  } else {
    productTypeSpecificDiv.innerHTML = '';
  }
});

/**
 * This part is responsible for capturing the values from the form fields and storing it
 * on "data". It also makes the requisition to the given route and returns messages if it either
 * succeeded or failed.
 */
    async function saveProduct() {
        const sku = document.querySelector('#sku').value;
        const name = document.querySelector('#name').value;
        const price = document.querySelector('#price').value;
        const productType = document.querySelector('#productType').value;
        let productData = {};

    switch (productType) {
        case 'Dvd':
            productData.size = document.querySelector('#size').value;
            break;
        case 'Book':
            productData.weight = document.querySelector('#weight').value;
            break;
        case 'Furniture':
            const height = document.querySelector('#height').value;
            const width = document.querySelector('#width').value;
            const length = document.querySelector('#length').value;
            productData.dimensions = `${height}x${width}x${length}`;
            break;
        default:
            break;
    }

    const data = {
        sku,
        name,
        price,
        product_type: productType,
        ...productData
    };
    const response = await fetch('http://localhost/productpage/backend/public/product/handleAddProduct', {
        method: 'POST',
        headers: {
        'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    });

    if (response.status === 200) {
        const result = await response.json();
        console.log(result.message);
        // Redirect to Product List page
        // window.location.href = '/product-list';
    } else {
        console.error(response.statusText);
    }
    }

    document.querySelector('#saveButton').addEventListener('click', (event) => {
    event.preventDefault();

        const skuInput = document.querySelector('#sku');
        const nameInput = document.querySelector('#name');
        const priceInput = document.querySelector('#price');
        const productTypeInput = document.querySelector('#productType');
        console.log(productTypeInput);
        let productTypeSpecificAttributeInput;

    switch (productTypeInput.value) {
        case 'Dvd':
        productTypeSpecificAttributeInput = document.querySelector('#size');
        break;
        case 'Book':
        productTypeSpecificAttributeInput = document.querySelector('#weight');
        break;
        case 'Furniture':
        productTypeSpecificAttributeInput = document.querySelector('#height');
        break;
        default:
        break;
    }

    if (!skuInput.checkValidity()) {
        showNotification('Please, provide a valid SKU');
    } else if (!nameInput.checkValidity()) {
        showNotification('Please, provide a valid name');
    } else if (!priceInput.checkValidity()) {
        showNotification('Please, provide a valid price');
    } else if (!productTypeInput.checkValidity()) {
        showNotification('Please, select a product type');
    } else if (productTypeSpecificAttributeInput && !productTypeSpecificAttributeInput.checkValidity()) {
        showNotification(`Please, provide a valid ${productTypeInput.value} ${getProductTypeSpecificAttributeLabel(productTypeInput.value)}`);
    } else {
        saveProduct();
    }
    });

    function showNotification(message) {
        const notification = document.createElement('div');
        notification.classList.add('notification');
        notification.textContent = message;
        document.body.appendChild(notification);
    setTimeout(() => {
        notification.remove();
    }, 5000);
    }

    function getProductTypeSpecificAttributeLabel(productType) {
        switch (productType) {
          case 'Dvd':
            return 'size (MB)';
          case 'Book':
            return 'weight (Kg)';
          case 'Furniture':
            return 'dimensions (HxWxL)';
          default:
            return '';
        }
      }

    /**
     * This part is responsible for making the "cancel" button redirect to the
     * product list page when clicked.
     */
    const cancelButton = document.querySelector('#cancelButton');
    cancelButton.addEventListener('click', () => {
        window.location.href = '/product-list';
    });