function switchImage(cnt){
                document.getElementById('video-'+cnt).style.display="none";
                document.getElementById('image-'+cnt).style.display="block";
                document.getElementById('switchVideo-'+cnt).style.display="block";
                document.getElementById('switchImage-'+cnt).style.display="none";
                document.getElementById('featured-'+cnt).style.display="block";
                document.getElementById('featured-'+cnt).style.width="440px";
                document.getElementById('featured-'+cnt).style.height="276px";
            }
function switchVideo(cnt){
                document.getElementById('featured-'+cnt).style.display="none";
                document.getElementById('video-'+cnt).style.display="block";
                document.getElementById('switchVideo-'+cnt).style.display="none";
                document.getElementById('switchImage-'+cnt).style.display="block";
            }

