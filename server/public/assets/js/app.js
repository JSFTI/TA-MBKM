function baseUrl(url){
  if (url[0] === '/')
    url = url.substring(1)

  return `${window.BASE_URL}/${url}`;
}

function handleProductImageError(e){
  e.target.nextElementSibling.classList.toggle('!hidden');
  e.target.classList.toggle('!hidden')
}

document.addEventListener('alpine:init', () => {
  Alpine.data('mainCarousel', () => {
    const state = {
      index: 0,
      glide: new Glide('.main-carousel', {
        autoplay: 5000
      })
    };

    state.glide.on('mount.after', () => {
      document.querySelector('.main-carousel .glide__slides').classList.remove('invisible');
    });
 
    state.glide.mount();

    return state;
  });
});