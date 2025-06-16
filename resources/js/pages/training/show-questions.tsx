import AppLayout from '@/layouts/app-layout';
import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import { toast } from 'sonner';
import { ArrowLeft, CheckCircle } from 'lucide-react';

interface Option {
    id: number;
    text: string;
    is_correct: boolean;
}

interface Question {
    id: number;
    text: string;
    options: Option[];
}

interface Topic {
    id: number;
    name: string;
    description: string;
}

interface Session {
    id: number;
}

interface Props {
    session: Session;
    topic?: Topic;
    questions: Question[];
}

export default function ShowQuestions({ session, topic, questions }: Props) {
    const [selectedAnswers, setSelectedAnswers] = useState<Record<number, number[]>>({});
    const [errors, setErrors] = useState<number[]>([]);

    const handleCheckboxChange = (questionId: number, optionId: number, checked: boolean) => {
        setSelectedAnswers(prev => {
            const currentSelections = prev[questionId] || [];
            const updatedSelections = checked ? [...currentSelections, optionId] : currentSelections.filter(id => id !== optionId);
            if (updatedSelections.length > 0 && errors.includes(questionId)) {
                setErrors(errs => errs.filter(id => id !== questionId));
            }
            return { ...prev, [questionId]: updatedSelections };
        });
    };

    const handleAutoSelect = () => {
        const newSelectedAnswers: Record<number, number[]> = {};
        questions.forEach(question => {
            const correctOptions = question.options.filter(option => option.is_correct).map(option => option.id);
            if (correctOptions.length > 0) {
                newSelectedAnswers[question.id] = correctOptions;
            }
        });
        setSelectedAnswers(newSelectedAnswers);
        setErrors([]);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        const unanswered = questions.filter(q => !selectedAnswers[q.id]?.length);
        if (unanswered.length) {
            setErrors(unanswered.map(q => q.id));
            return;
        }
        router.post(
            route('training.questions.submit', { session: session.id, topic: topic?.id }),
            { answers: selectedAnswers },
            {
                onSuccess: () => toast.success('Questions submitted successfully!'),
                onError: () => toast.error('Failed to submit questions. Please try again.'),
            }
        );
    };

    return (
        <AppLayout>
            <Head title="Dashboard" />
            <div className={`w-full max-w-5xl mx-auto pt-12 flex ${!topic ? 'justify-end' : 'justify-between'}`}>
                {topic && (
                    <Link
                        href={route('training.questions.watch', { session: session.id, topic: topic.id })}
                        className="flex items-center text-sm text-muted-foreground"
                    >
                        <ArrowLeft className="w-4 h-4 mr-3 text-sm text-muted-foreground" />
                        Go back
                    </Link>
                )}


                {process.env.NODE_ENV === 'development' && (
                    <Button
                        variant="outline"
                        size="sm"
                        onClick={handleAutoSelect}
                        className="flex items-center gap-2"
                    >
                        <CheckCircle className="w-4 h-4" />
                        Auto-select correct answers
                    </Button>
                )}


            </div>
            <Card className="w-full max-w-5xl border-none shadow-none mx-auto">
                <CardHeader className="pb-2">
                    <CardTitle className="text-2xl md:text-3xl text-center font-bold">
                        {topic?.name ? `Retaking test for topic ${topic.name}` : 'Security Awareness Training'}
                    </CardTitle>
                    <CardDescription className="text-center mt-2">
                        {topic?.description || 'This is a security awareness training. This test consits of multiple choice questions.'}
                    </CardDescription>
                </CardHeader>
                <form onSubmit={handleSubmit}>
                    <CardContent className="space-y-10 px-6 py-8 border-none">
                        {questions.map((question, idx) => (
                            <div key={question.id} className="space-y-3">
                                <h3 className="font-medium text-lg text-gray-800 dark:text-gray-200">{idx + 1}. {question.text}</h3>
                                <div className="space-y-3 mt-4">
                                    {question.options.map(option => (
                                        <div key={option.id} className="flex items-center space-x-3 py-1">
                                            <Checkbox
                                                id={`option-${option.id}`}
                                                checked={selectedAnswers[question.id]?.includes(option.id) || false}
                                                onCheckedChange={checked =>
                                                    handleCheckboxChange(question.id, option.id, checked as boolean)
                                                }
                                            />
                                            <Label
                                                htmlFor={`option-${option.id}`}
                                                className="text-sm font-normal leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                                {option.text}
                                            </Label>
                                        </div>
                                    ))}
                                </div>
                                {errors.includes(question.id) && (
                                    <p className="text-sm text-red-500 mt-1">Please select at least one option.</p>
                                )}
                                {idx < questions.length - 1 && <Separator className="mt-8" />}
                            </div>
                        ))}
                    </CardContent>
                    <CardFooter className="pb-8">
                        <Button type="submit" className="w-full max-w-xs mx-auto">Submit</Button>
                    </CardFooter>
                </form>
            </Card>
        </AppLayout>
    );
}
