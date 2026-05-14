/* ==========================================
   Mock Data for Career Insights
   ========================================== */

const majors = [
    { id: 'business', name: 'Business & Management', icon: 'bi-briefcase', color: 'primary' },
    { id: 'technology', name: 'Technology & IT', icon: 'bi-cpu', color: 'info' },
    { id: 'healthcare', name: 'Healthcare & Medicine', icon: 'bi-heart-pulse', color: 'danger' },
    { id: 'engineering', name: 'Engineering', icon: 'bi-gear', color: 'warning' },
    { id: 'education', name: 'Education', icon: 'bi-book', color: 'success' },
    { id: 'arts', name: 'Arts & Design', icon: 'bi-palette', color: 'secondary' }
];

const jobRoles = [
    {
        id: 'job1',
        title: 'Software Engineer',
        major: 'Technology & IT',
        subMajor: 'Software Development',
        description: 'Design, develop, and maintain software applications and systems',
        salaryRange: '12,000 - 25,000 SAR/month',
        averageRating: 4.5,
        evaluations: [
            {
                id: 'eval1',
                userId: 'user1',
                userName: 'Ahmed Al-Rashid',
                company: 'Saudi Aramco',
                rating: 5,
                experience: 'Excellent career path with great opportunities for growth. The work-life balance is good and the compensation is competitive.',
                advantages: ['High salary', 'Remote work options', 'Continuous learning', 'Good benefits'],
                disadvantages: ['Can be stressful during deadlines', 'Long hours sometimes'],
                datePosted: '2026-03-15'
            },
            {
                id: 'eval2',
                userId: 'user2',
                userName: 'Sara Mohammed',
                company: 'STC',
                rating: 4,
                experience: 'Great role for those passionate about technology. Challenging but rewarding work environment.',
                advantages: ['Modern tech stack', 'Team collaboration', 'Career advancement'],
                disadvantages: ['Need constant upskilling', 'Competitive environment'],
                datePosted: '2026-03-10'
            }
        ]
    },
    {
        id: 'job2',
        title: 'Data Analyst',
        major: 'Technology & IT',
        subMajor: 'Data Science',
        description: 'Analyze complex data sets to help organizations make informed decisions',
        salaryRange: '10,000 - 20,000 SAR/month',
        averageRating: 4.3,
        evaluations: [
            {
                id: 'eval3',
                userId: 'user3',
                userName: 'Khalid Abdullah',
                company: 'NEOM',
                rating: 4,
                experience: 'Fascinating work analyzing data trends. Requires strong analytical skills and attention to detail.',
                advantages: ['Data-driven insights', 'Problem-solving', 'High demand'],
                disadvantages: ['Can be repetitive', 'Requires advanced tools knowledge'],
                datePosted: '2026-04-01'
            }
        ]
    },
    {
        id: 'job3',
        title: 'Marketing Manager',
        major: 'Business & Management',
        subMajor: 'Marketing',
        description: 'Develop and execute marketing strategies to promote products and services',
        salaryRange: '15,000 - 30,000 SAR/month',
        averageRating: 4.7,
        evaluations: [
            {
                id: 'eval4',
                userId: 'user4',
                userName: 'Noura Al-Faisal',
                company: 'Almarai',
                rating: 5,
                experience: 'Dynamic role with excellent opportunities for creativity and innovation. Perfect for those who love strategic thinking.',
                advantages: ['Creative freedom', 'Strategic impact', 'Networking opportunities', 'Variety of projects'],
                disadvantages: ['High pressure', 'Results-driven'],
                datePosted: '2026-04-05'
            }
        ]
    },
    {
        id: 'job4',
        title: 'Registered Nurse',
        major: 'Healthcare & Medicine',
        subMajor: 'Nursing',
        description: 'Provide patient care and support in healthcare facilities',
        salaryRange: '8,000 - 18,000 SAR/month',
        averageRating: 4.6,
        evaluations: [
            {
                id: 'eval5',
                userId: 'user5',
                userName: 'Fatima Hassan',
                company: 'King Faisal Hospital',
                rating: 5,
                experience: 'Rewarding career helping people. Requires dedication and compassion but incredibly fulfilling.',
                advantages: ['Making a difference', 'Job security', 'Respected profession'],
                disadvantages: ['Long shifts', 'Emotionally demanding'],
                datePosted: '2026-03-20'
            }
        ]
    },
    {
        id: 'job5',
        title: 'Civil Engineer',
        major: 'Engineering',
        subMajor: 'Civil Engineering',
        description: 'Design and oversee construction of infrastructure projects',
        salaryRange: '12,000 - 28,000 SAR/month',
        averageRating: 4.4,
        evaluations: []
    },
    {
        id: 'job6',
        title: 'Graphic Designer',
        major: 'Arts & Design',
        subMajor: 'Graphic Design',
        description: 'Create visual content for digital and print media',
        salaryRange: '7,000 - 15,000 SAR/month',
        averageRating: 4.2,
        evaluations: []
    }
];

// Get jobs by major
function getJobsByMajor(majorId) {
    const majorName = majors.find(m => m.id === majorId)?.name;
    return jobRoles.filter(job => job.major === majorName);
}

// Get job by ID
function getJobById(jobId) {
    return jobRoles.find(job => job.id === jobId);
}

// Search jobs
function searchJobs(query) {
    const lowerQuery = query.toLowerCase();
    return jobRoles.filter(job => 
        job.title.toLowerCase().includes(lowerQuery) ||
        job.major.toLowerCase().includes(lowerQuery) ||
        job.description.toLowerCase().includes(lowerQuery)
    );
}
