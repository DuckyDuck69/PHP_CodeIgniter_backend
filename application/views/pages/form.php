<html>
    <head>
        <title>Submit Page</title>
    </head>
    <body>
        
        <h1><?php echo 'Fill out this form'; ?></h1>
        <form id = "form" method="post"  action="<?= site_url('forms/submit'); ?>">
            <label>Email: 
                <input type="email" id="email" name="email" required><br><br>
            </label>
            <label >Họ và tên:
                <input type="text" id="fullname" name="fullname" required><br><br>
            </label>
            <br>

            <!-- group gender as a radio fieldset -->
            <label>Giới tính: 
                <input type="radio" name="gender" value="Female"> Female
                <input type="radio" name="gender" value="Male"> Male
            </label>
            <br>
            <br>
            <label>Tuổi
                <input type="number" min="0" max="117" step="1" id="age" name="age" required><br><br>
            </label>
            <label>Nghề nghiệp
                <input type="text" id="job" name="job" required><br><br>
            </label>
           
            <input type="hidden" name="latitude" id="lat">
            <input type="hidden" name="longitude" id="long">
            <button type="submit">Submit</button>
        </form> 
        <a href="login">Are you an admin?</a>
    </body>
    <script>
        const form = document.getElementById('form')
        const latitude = document.getElementById('lat')
        const longitude = document.getElementById('long')
        form.addEventListener('submit', (e)=>{
            e.preventDefault() //wait for the geographic 

            if(!navigator.geolocation){
                form.submit();
                return
            }

            navigator.geolocation.getCurrentPosition((position) =>{
                latitude.value = position.coords.latitude;
                longitude.value = position.coords.longitude;
                form.submit();
            }, 
            ()=>{
                form.submit() 
            })
        })
        
        
    </script>
</html>