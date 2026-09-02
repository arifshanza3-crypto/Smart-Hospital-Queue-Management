// document.addEventListener('DOMContentLoaded', () => {
//     const counters = document.querySelectorAll('.counter');
//     const speed = 200; // The lower the number, the faster the count

//     const startCounters = (entries, observer) => {
//         entries.forEach(entry => {
//             if (entry.isIntersecting) {
//                 entry.target.classList.add('active'); // Add active class to section
                
//                 counters.forEach(counter => {
//                     const updateCount = () => {
//                         const target = +counter.getAttribute('data-target');
//                         const count = +counter.innerText;
//                         const inc = target / speed;

//                         if (count < target) {
//                             counter.innerText = Math.ceil(count + inc);
//                             setTimeout(updateCount, 1);
//                         } else {
//                             counter.innerText = target;
//                         }
//                     };
//                     updateCount();
//                 });
//                 observer.unobserve(entry.target); // Stop observing once animated
//             }
//         });
//     };

//     const section = document.querySelector('.special-intro-section');
//     if (section) {
//         const observerOptions = {
//             root: null,
//             rootMargin: '0px',
//             threshold: 0.4 // Trigger when 40% of the section is visible
//         };
//         const observer = new IntersectionObserver(startCounters, observerOptions);
//         observer.observe(section);
//     }
// });