let listproductHTML = document.querySelector('.listproduct')
let listproduct = [];

const addDataToHTML = () => {
    listproductHTML.innerHTML = '';
    if(listproduct.length > 0){
        listproduct.forEach(product => {
            let newProduct = document.createElement('div');
            newProduct.classList.add('item');
            newProduct.innerHTML = `
            <img src="${product.image}" alt="">
            <h2>${product.name}</h2>
            <div class="price">RM ${product.price}</div>
            `;
            listproductHTML.appendChild(newProduct);
        })
    }
}
const initApp = () => {
    //get data from json
    fetch('products.js')
    .then(response => response.json())
    .then(data => {
        listproduct = data;
        addDataToHTML();
    })
}
initApp();
