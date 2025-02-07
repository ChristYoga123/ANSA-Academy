@props(['mentor'])
<div class="course-details__Instructor mb-5">
    <div class="course-details__Instructor-img">
        <img src="{{ $mentor->getFirstMediaUrl('mentor-poster') }}" alt="{{ $mentor->name }}">
    </div>
    <div class="course-details__Instructor-content">
        <div class="course-details__Instructor-client-name-box-and-view">
            <div class="course-details__Instructor-client-name-box">
                <h4>{{ $mentor->name }}</h4>
                <p>{{ $mentor->custom_fields['bidang_mentor'] ?? 'Mentor' }}
                </p>
            </div>
        </div>
        <ul class="course-details__Instructor-ratting-list list-unstyled mb-3">
            <li>
                {{-- average mentor rating --}}
                <p><span class="fas fa-star"></span>({{ $mentor->testimoni->avg('rating') ?? 0 }}
                    / 5.0 Rating)
                </p>
            </li>
        </ul>
        <div class="course-details__Instructor-social">
            <a href="{{ $mentor->custom_fields['linkedin'] ?? '#' }}"><span class="fab fa-linkedin-in"></span></a>
            <a href="{{ $mentor->custom_fields['linkedin'] ?? '#' }}"><span class="fab fa-instagram"></span></a>
        </div>
    </div>
</div>
