<html>
    <head>

    </head>
    <body>
        <div id="app">
            <input placeholder="Search product..."
                    data-bind="value: search, events: { input: applyFilter }" />
            <div data-role="grid"
                data-bind="source: products"
                data-filterable='{"mode":"row"}'
                data-columns="[
                    { field: 'id', title: 'ID' },
                    { field: 'name', title: 'Product Name' },
                    { field: 'category', title: 'Category' },
                    { field: 'price', title: 'Price', format: '{0:c}' }
                ]"
                data-pageable="true"
                data-scrollable="true">
            </div>
        </div>
    </body>
        <script>
            var viewModel = kendo.observable({
                products: new kendo.data.DataSource({
                    data: [
                        { id: 1, name: "Laptop", category: "Electronics", price: 1200 },
                        { id: 2, name: "Headphones", category: "Electronics", price: 150 },
                        { id: 3, name: "Coffee Mug", category: "Kitchen", price: 12 },
                        { id: 4, name: "Notebook", category: "Stationery", price: 5 },
                        { id: 5, name: "Office Chair", category: "Furniture", price: 300 }
                    ],
                    pageSize: 3
                })
            });

            kendo.bind($("#app"), viewModel);
        </script>
</html>