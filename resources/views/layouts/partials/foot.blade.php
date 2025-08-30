<!-- JavaScripts
	============================================= -->

<script src="{{ asset('assets/js/plugins.min.js')}}"></script>
<script src="{{ asset('assets/js/functions.bundle.js')}}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carousels = document.querySelectorAll('.carousel-container');
        
        carousels.forEach(carousel => {
            const type = carousel.dataset.type;
            const prevBtn = carousel.querySelector('.carousel-prev');
            const nextBtn = carousel.querySelector('.carousel-next');
            const images = carousel.querySelectorAll('.image-container');
            let currentIndex = 0;

            function showImage(index) {
                images.forEach((img, i) => {
                    img.classList.toggle('active', i === index);
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    currentIndex = (currentIndex - 1 + images.length) % images.length;
                    showImage(currentIndex);
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    currentIndex = (currentIndex + 1) % images.length;
                    showImage(currentIndex);
                });
            }
        });
    });
    document.addEventListener("livewire:navigated", function() {
        window.jQuery = window.$ = jQuery;
        
    });
</script>
