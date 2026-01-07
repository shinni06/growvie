function tab(t){
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
            });

            document.getElementById('tab' + t).classList.add('active');
            
            if(t == 1){
                document.getElementById('content1').style.display = 'grid';
                document.getElementById('content2').style.display = 'none';
                document.getElementById('content3').style.display = 'none';
                document.getElementById('content4').style.display = 'none';
                document.getElementById('content5').style.display = 'none';
                document.getElementById('content6').style.display = 'none';
            }else if(t == 2){
                document.getElementById('content1').style.display = 'none';
                document.getElementById('content2').style.display = 'block';
                document.getElementById('content3').style.display = 'none';
                document.getElementById('content4').style.display = 'none';
                document.getElementById('content5').style.display = 'none';
                document.getElementById('content6').style.display = 'none';
            }else if(t == 3){
                document.getElementById('content1').style.display = 'none';
                document.getElementById('content2').style.display = 'none';
                document.getElementById('content3').style.display = 'block';
                document.getElementById('content4').style.display = 'none';
                document.getElementById('content5').style.display = 'none';
                document.getElementById('content6').style.display = 'none';
            }else if (t == 4){
                document.getElementById('content1').style.display = 'none';
                document.getElementById('content2').style.display = 'none';
                document.getElementById('content3').style.display = 'none';
                document.getElementById('content4').style.display = 'block';
                document.getElementById('content5').style.display = 'none';
                document.getElementById('content6').style.display = 'none';
            }else if (t == 5){
                document.getElementById('content1').style.display = 'none';
                document.getElementById('content2').style.display = 'none';
                document.getElementById('content3').style.display = 'none';
                document.getElementById('content4').style.display = 'none';
                document.getElementById('content5').style.display = 'block';
                document.getElementById('content6').style.display = 'none';
            }else{
                document.getElementById('content1').style.display = 'none';
                document.getElementById('content2').style.display = 'none';
                document.getElementById('content3').style.display = 'none';
                document.getElementById('content4').style.display = 'none';
                document.getElementById('content5').style.display = 'none';
                document.getElementById('content6').style.display = 'block';
            }
        }
        window.addEventListener('DOMContentLoaded', function() {
            document.getElementById('tab1').classList.add('active');
        });