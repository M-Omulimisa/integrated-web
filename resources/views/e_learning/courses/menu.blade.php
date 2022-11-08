<ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('e-learning.courses.show', $course->id) }}">Course profile</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ 
            $request->segment(3) == 'analytics' || 
            $request->segment(2) == 'analytics' ? 'active' : '' 
        }}" href="{{ url('e-learning/courses/analytics/'.$course->id.'/overview') }}">Analytics</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ 
            $request->segment(3) == 'content' || 
            $request->segment(3) == 'chapters' || 
            $request->segment(2) == 'chapters' || 
            $request->segment(3) == 'lectures' || 
            $request->segment(2) == 'lectures' || 
            $request->segment(3) == 'weeks' || 
            $request->segment(2) == 'weeks' || 
            $request->segment(3) == 'assignments' || 
            $request->segment(2) == 'assignments' || 
            $request->segment(3) == 'general-assignments' || 
            $request->segment(2) == 'general-assignments' 
            ? 'active' : '' 
        }}" href="{{ url('e-learning/courses/content/'.$course->id) }}">Lessons</a>
    </li>
    {{-- <li class="nav-item">
        <a class="nav-link {{ 
            $request->segment(3) == 'board' || 
            $request->segment(3) == 'announcements' || 
            $request->segment(2) == 'announcements'
            ? 'active' : '' 
        }}" href="{{ url('e-learning/courses/board/'.$course->id) }}">Announcements</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ 
            $request->segment(3) == 'source' || 
            $request->segment(3) == 'sources' || 
            $request->segment(3) == 'resources' || 
            $request->segment(2) == 'resources'
            ? 'active' : '' 
        }}" href="{{ url('e-learning/courses/sources/'.$course->id) }}">Resources</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ 
            $request->segment(3) == 'forums' 
            ? 'active' : '' 
        }}" href="{{ url('e-learning/courses/forums/'.$course->id) }}">Forums</a>
    </li> --}}
    <li class="nav-item">
        <a class="nav-link {{ 
            $request->segment(3) == 'polls' 
            ? 'active' : '' 
        }}" href="#">Polls</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ 
            $request->segment(3) == 'enrolled-students' 
            ? 'active' : '' 
        }}" href="{{ url('e-learning/courses/enrolled-students/'.$course->id) }}">Students</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ 
            $request->segment(3) == 'course-instructions' 
            ? 'active' : '' 
        }}" href="{{ url('e-learning/courses/course-instructions/'.$course->id) }}">Instructions</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ 
            $request->segment(3) == 'course-messages' 
            ? 'active' : '' 
        }}" href="{{ url('e-learning/courses/course-messages/'.$course->id) }}">Messages</a>
    </li>
</ul>