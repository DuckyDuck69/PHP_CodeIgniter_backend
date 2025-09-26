<html>
    <head>
        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2021.3.914/styles/kendo.common.min.css">
        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2021.3.914/styles/kendo.bootstrap.min.css">            
        
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Kendo UI R3 2021 (2021.3.914) JS -->
        <script src="https://kendo.cdn.telerik.com/2021.3.914/js/kendo.all.min.js"></script>
    </head>
    <style>
        #header.k-appbar {
        background: transparent;    
        border-color: transparent;  
        box-shadow: none;          
        }
    </style>
    <body>
        <div id="header"></div>
    </body>
    <script>
         $("#header").kendoAppBar({
            items: [
                {type: "spacer"},
                {type:"contentItem", template:"<div style='margin-top: 10px; padding:0 12px;font-weight:700;font-size:35px'>Magic Email Website</div>" },
                {type: "spacer"}
            ]
        });
    </script>
</html>